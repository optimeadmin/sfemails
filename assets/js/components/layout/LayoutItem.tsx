import React, { useState } from 'react'
import { ExistentLayout, Layout } from '../../types'
import { stringAsDate } from '../../utils/dates'
import { Button, Modal } from 'react-bootstrap'
import { useUrl } from '../../contexts/UrlContext'
import { Preview } from '../preview/Preview'

export function LayoutItem ({ layout }: { layout: ExistentLayout }) {
  return (
    <tr className='table-row-middle'>
      <td>{layout.id}</td>
      <td>
        {layout.description}
        <small className="d-block uuid-small">{layout.uuid}</small>
      </td>
      <td>{stringAsDate(layout.dates.createdAt)}</td>
      <td>{stringAsDate(layout.dates.updatedAt)}</td>
      <td>
        <div className="d-flex gap-1">
          <Button size="sm" variant="outline-primary">Edit</Button>
          <PreviewAction uuid={layout.uuid as string}/>
        </div>
      </td>
    </tr>
  )
}

function PreviewAction ({ uuid }: { uuid: string }) {
  const { apiUrl } = useUrl()
  const [showPreview, setShowPreview] = useState(false)
  const previewUrl = `${apiUrl}/preview/layout/${uuid}`

  return (
    <>
      <Button size="sm" variant="outline-secondary" onClick={() => setShowPreview(true)}>Preview</Button>
      <Modal show={showPreview} onHide={() => setShowPreview(false)} size="lg">
        <Modal.Header closeButton>
          <Modal.Title>Hi!</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Preview url={previewUrl}/>
        </Modal.Body>
      </Modal>
    </>
  )
}