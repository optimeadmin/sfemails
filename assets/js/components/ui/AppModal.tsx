import React, { useLayoutEffect, useRef } from 'react'
import { Modal, ModalProps } from 'react-bootstrap'

export function AppModal (props: ModalProps) {
  const { show, onHide, onEntered, onExiting } = props
  const $modal = useRef(null)
  const itemsCleaning = useRef<Array<() => void>>([])
  const onHideRef = useRef(onHide)

  useLayoutEffect(() => {
    onHideRef.current = onHide
  })

  function hideModal (event: Event) {
    event.preventDefault()
    onHide?.()
  }

  function addListeners ($container: HTMLElement) {
    itemsCleaning.current = []

    $container.querySelectorAll('[data-bs-hide]').forEach(item => {
      item.addEventListener('click', hideModal)
      itemsCleaning.current.push(() => item.removeEventListener('click', hideModal))
    })
  }

  function removeListeners () {
    itemsCleaning.current.forEach(callback => callback())
    itemsCleaning.current = []
  }

  return (
    <Modal
      ref={$modal}
      {...props}
      onEntered={(node, isAppearing) => {
        addListeners(node)
        onEntered?.(node, isAppearing)
      }}
      onExiting={(node) => {
        removeListeners()
        onExiting?.(node)
      }}
    />
  )
}