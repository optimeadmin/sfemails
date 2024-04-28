import React, { ReactNode, useState } from 'react'
import { Button, ButtonProps, ModalProps } from 'react-bootstrap'
import { AppModal } from './AppModal.tsx'

type ButtonModalProps = Omit<ButtonProps, 'onClick'> & {
  modalProps?: Omit<ModalProps, 'onHide' | 'show'>,
  modalContent: ReactNode
  onHide?: () => void,
}

export function ButtonModal ({ modalContent, modalProps, onHide, ...buttonProps }: ButtonModalProps) {
  const [showModal, setShowModal] = useState(false)

  return (
    <>
      <Button onClick={() => setShowModal(true)} {...buttonProps} />
      <AppModal show={showModal} onHide={() => {
        setShowModal(false)
        onHide?.()
      }} {...modalProps}>
        {modalContent}
      </AppModal>
    </>
  )
}