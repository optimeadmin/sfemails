import React, { useState } from 'react'
import { Button, Modal } from 'react-bootstrap'
import { LayoutForm } from '../layout/LayoutForm.tsx'
import { AppModal } from '../ui/AppModal.tsx'

export function CreateLayoutInConfig () {
  const [showForm, setShowForm] = useState(false)
  return (
    <>
      <Button variant="outline-dark" onClick={() => setShowForm(true)}>Create</Button>
      <AppModal show={showForm} onHide={() => setShowForm(false)} size="xl" backdrop='static'>
        <Modal.Header closeButton>
          <Modal.Title>Create Layout</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <LayoutForm fromModal onSuccess={() => {
            setShowForm(false)
          }}/>
        </Modal.Body>
      </AppModal>
    </>
  )
}