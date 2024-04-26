import React from 'react'
import { Modal } from 'react-bootstrap'
import { CloseModal, useHideModal } from '../ui/AppModal.tsx'

export function SendTest ({ uuid }: { uuid: string }) {
  const hideModal = useHideModal()

  return (
    <>
      <Modal.Header closeButton>
        <Modal.Title>Send Test</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <div>
          <h1>Send test</h1>
        </div>
      </Modal.Body>
      <Modal.Footer>
        <CloseModal>Close</CloseModal>
      </Modal.Footer>
    </>
  )
}