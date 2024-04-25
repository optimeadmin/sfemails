import React, { createContext, PropsWithChildren, useContext, useState } from 'react'
import { Button, Modal } from 'react-bootstrap'
import { LayoutForm } from '../layout/LayoutForm.tsx'
import { AppModal } from '../ui/AppModal.tsx'

type ContextType = {
  showForm: boolean,
  setShowForm: (show: boolean) => void,
}

const Context = createContext<ContextType | null>(null)

export function CreateLayoutInConfigProvider ({ children }: PropsWithChildren) {
  const [showForm, setShowForm] = useState(false)

  return (
    <Context.Provider value={{ showForm, setShowForm }}>
      {children}
      <CreateLayoutInConfig/>
    </Context.Provider>
  )
}

function useCreateLayoutInConfig (): ContextType {
  const context = useContext(Context)

  if (!context) throw new Error('useCreateLayoutInConfig must be inside in <CreateLayoutInConfigProvider/>')

  return context
}

export function CreateLayoutInConfigButton () {
  const { showForm, setShowForm } = useCreateLayoutInConfig()

  return (
    <Button variant="outline-dark" onClick={() => setShowForm(true)}>Create</Button>
  )
}

function CreateLayoutInConfig () {
  const { showForm, setShowForm } = useCreateLayoutInConfig()

  return (
    <AppModal show={showForm} onHide={() => setShowForm(false)} size="xl" backdrop="static">
      <Modal.Header closeButton>
        <Modal.Title>Create Layout</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <LayoutForm fromModal onSuccess={() => {
          setShowForm(false)
        }}/>
      </Modal.Body>
    </AppModal>
  )
}