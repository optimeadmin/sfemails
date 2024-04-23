import React, { useState } from 'react'
import { Button, Modal } from 'react-bootstrap'
import { useUrl } from '../../contexts/UrlContext'
import { Preview } from '../preview/Preview'

type Props = {
  uuid: string
  title: string
}

export function PreviewLayoutAction ({ uuid, title }: Props) {
  const { apiUrl } = useUrl()
  const [showPreview, setShowPreview] = useState(false)
  const previewUrl = `${apiUrl}/preview/layout/${uuid}`

  return (
    <>
      <Button size="sm" variant="outline-secondary" onClick={() => setShowPreview(true)}>{title}</Button>
      <Modal show={showPreview} onHide={() => setShowPreview(false)} size="lg">
        <Modal.Header closeButton>
          <Modal.Title>Preview</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Preview url={previewUrl}/>
        </Modal.Body>
      </Modal>
    </>
  )
}