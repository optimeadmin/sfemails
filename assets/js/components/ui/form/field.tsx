import React, { createContext, useContext, useEffect, useRef } from 'react'
import { Form, FormGroupProps, FormLabelProps, Spinner } from 'react-bootstrap'
import { get, useFormState } from 'react-hook-form'
import { clsx } from 'clsx'

type FormRowContextType = {
  name?: string
}

const FormRowContext = createContext<FormRowContextType | null>(null)

type FormRowProps = {
  name?: string
} & FormGroupProps

export function FormRow ({ name, children, ...props }: FormRowProps) {
  const $container = useRef<HTMLElement | null>()
  const { errors } = useFormState({ name, exact: true })
  const isValid = !name || !get(errors, name)

  useEffect(() => {
    if (!$container.current) return

    if (isValid) {
      $container.current?.querySelector('.form-control, .form-select, .CodeMirror')?.classList.remove('is-invalid')
    } else {
      $container.current?.querySelector('.form-control, .form-select, .CodeMirror')?.classList.add('is-invalid')
    }
  }, [$container, isValid])

  return (
    <Form.Group {...props} ref={$container}>
      <FormRowContext.Provider value={{ name }}>
        {children}
      </FormRowContext.Provider>
    </Form.Group>
  )
}

function useFormRow () {
  const context = useContext(FormRowContext)

  if (!context) {
    throw new Error('useFormRow must be used inside FormRow')
  }

  return context
}

type FormLabelPropsWithLoader = FormLabelProps & {
  showLoader?: boolean
}

export function FormLabel ({ showLoader, className, children, ...props }: FormLabelPropsWithLoader) {
  return (
    <Form.Label {...props} className={clsx(className, 'd-flex justify-content-between align-items-center gap-1')}>
      {children}
      {showLoader && <Spinner size="sm"/>}
    </Form.Label>
  )
}

export function FormErrors () {
  const { name } = useFormRow()
  const { errors } = useFormState({ name, exact: true })

  if (!name) return null

  const field = get(errors, name)

  if (!field) return null

  return (
    <div className="text-danger">
      {field.message || (field.type === 'required' ? 'This field is required' : 'Error')}
    </div>
  )
}