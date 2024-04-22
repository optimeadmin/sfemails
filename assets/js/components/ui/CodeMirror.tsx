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
  const defaultValueRef = useRef<any>(value)
  const editor = useRef<any>(null)

  useInsertionEffect(() => {
    onChangeRef.current = onChange
    defaultValueRef.current = value
  })

  useEffect(() => {
    if (!$input.current) return

    const editor = GlobalCodeMirror.fromTextArea($input.current, {
      lineNumbers: true,
      mode: { name: 'twig', base: 'text/html' },
      theme: 'idea',
    })

    editor.on('change', (obj: any, { origin }: { origin: string }) => {
      if (origin === 'setValue') return // prevent infinite rerender

      onChangeRef.current?.(editor.getValue())
    })

    if (defaultValueRef.current) {
      editor.setValue(defaultValueRef.current)
    }

    return () => {
      editor.toTextArea()
    }
  }, [$input, onChangeRef, defaultValueRef])

  return (
    <textarea ref={$input}></textarea>
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