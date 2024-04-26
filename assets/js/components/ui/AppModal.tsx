import React, { createContext, useContext, useRef } from 'react'
import { Button, ButtonProps, Modal, ModalProps } from 'react-bootstrap'

type ModalContextType = {
  hide: () => void,
}

const ModalContext = createContext<ModalContextType | null>(null)

export function AppModal (props: ModalProps) {
  const { show, onHide } = props
  const $modal = useRef(null)

  function hide () {
    onHide?.()
  }

  return (
    <ModalContext.Provider value={{ hide }}>
      <Modal
        ref={$modal}
        {...props}
      />
    </ModalContext.Provider>
  )
}

export function useHideModal () {
  const context = useContext(ModalContext)

  if (!context) throw new Error('useHideModal must be used inside <AppModal />')

  return context.hide
}

type CloseModalProps = Omit<ButtonProps, 'onClick'>

export function CloseModal (props: CloseModalProps) {
  const { variant, ...btnProps } = props
  const hideModal = useHideModal()

  return <Button variant={variant ?? 'outline-secondary'} onClick={hideModal} {...btnProps}/>
}