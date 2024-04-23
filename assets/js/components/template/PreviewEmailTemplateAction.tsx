import React, { useState } from 'react'
import { Button, Modal } from 'react-bootstrap'
import { useUrl } from '../../contexts/UrlContext'
import { Preview } from '../preview/Preview'

type Props = {
  uuid: string|null
  title: string,
  buttonProps?: React.ComponentProps<typeof Button>
}

export function PreviewEmailTemplateAction ({ uuid, title, buttonProps }: Props) {
  const { apiUrl } = useUrl()
  const [showPreview, setShowPreview] = useState(false)
  const previewUrl = `${apiUrl}/preview/template/${uuid}`
  const disabled = !Boolean(uuid)

  return (
    <>
      <Button
        disabled={disabled}
        className="text-nowrap"
        size="sm"
        variant="outline-secondary"
        onClick={() => setShowPreview(true)}
      >{title}</Button>
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