import React, { useEffect, useInsertionEffect, useRef } from 'react'
import { Controller } from 'react-hook-form'

// @ts-ignore
const GlobalCodeMirror = window['CodeMirror']

type OnChangeType = (value: any) => void

type CodeMirrorProps = {
  value?: any,
  onChange?: OnChangeType
}

export function CodeMirror ({ onChange, value }: CodeMirrorProps) {
  const $input = useRef<HTMLTextAreaElement | null>(null)
  const onChangeRef = useRef<OnChangeType | undefined>(undefined)

  useInsertionEffect(() => {
    onChangeRef.current = onChange
  })

  useEffect(() => {
    if (!$input.current) return

    const editor = GlobalCodeMirror.fromTextArea($input.current, {
      lineNumbers: true,
      mode: { name: 'twig', base: 'text/html' },
      theme: 'idea',
    })

    editor.on('change', () => {
      onChangeRef.current?.(editor.getValue())
    })

    return () => {
      editor.toTextArea()
    }
  }, [$input, onChangeRef])

  return (
    <textarea ref={$input} defaultValue={value}></textarea>
  )
}

export function ControlledCodeMirror ({ name, rules }: { name: string, rules: any }) {
  return (
    <Controller
      rules={rules}
      name={name}
      render={({ field: { onChange, value } }) => (
        <CodeMirror value={value} onChange={onChange}/>
      )}
    />
  )
}