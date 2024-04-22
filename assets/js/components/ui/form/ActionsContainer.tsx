import React, { PropsWithChildren } from 'react'

export function ActionsContainer ({ children }: PropsWithChildren) {
  return (
    <div className="d-flex justify-content-end gap-2 mt-3 border-top pt-3">
      {children}
    </div>
  )
}