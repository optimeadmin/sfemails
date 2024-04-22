import React from 'react'
import { Button, ButtonProps, Spinner } from 'react-bootstrap'

type Props = {
  isLoading?: boolean,
} & ButtonProps

export function ButtonWithLoading ({ isLoading = false, disabled, children, ...props }: Props) {
  return (
    <Button {...props} disabled={isLoading || disabled}>
      {isLoading && <Spinner size='sm' className='me-1' />}
      {children}
    </Button>
  )
}