import React, { useEffect, useInsertionEffect, useRef } from 'react'
import { Controller } from 'react-hook-form'

// @ts-ignore
const GlobalCodeMirror = window['CodeMirror']

type OnChangeType = (value: any) => void
type ModeType = 'twig' | 'yaml'

type CodeMirrorProps = {
  value?: any,
  onChange?: OnChangeType,
  type?: ModeType
}

export function CodeMirror ({ onChange, value, type = 'twig' }: CodeMirrorProps) {
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

    let mode

    if (type === 'yaml') {
      mode = { name: 'yaml' }
    } else {
      mode = { name: 'twig', base: 'text/html' }
    }

    const editor = GlobalCodeMirror.fromTextArea($input.current, {
      lineNumbers: true,
      mode,
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
  }, [$input, onChangeRef, defaultValueRef, type])

  return (
    <textarea ref={$input}></textarea>
  )
}

type ControlledCodeMirrorProps = CodeMirrorProps & {
  name: string,
  rules?: any,
  type?: ModeType
}

export function ControlledCodeMirror ({ name, rules = {}, type }: ControlledCodeMirrorProps) {
  return (
    <Controller
      rules={rules}
      name={name}
      render={({ field: { onChange, value } }) => (
        <CodeMirror type={type} value={value} onChange={onChange}/>
      )}
    />
  )
}