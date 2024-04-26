import React, { useState } from 'react'
import { ExistentEmailTemplate } from '../../types'
import { stringAsDate } from '../../utils/dates'
import { Link } from 'react-router-dom'
import { PreviewEmailTemplateAction } from './PreviewEmailTemplateAction.tsx'
import { Button } from 'react-bootstrap'
import { AppModal } from '../ui/AppModal.tsx'
import { SendTest } from './SendTest.tsx'

export function EmailTemplateItem ({ emailTemplate }: { emailTemplate: ExistentEmailTemplate }) {
  return (
    <tr className="table-row-middle">
      <td>
        <div className="user-select-all mb-2">{emailTemplate.configCode}</div>
        <small className="d-block uuid-small user-select-all">{emailTemplate.uuid}</small>
      </td>
      <td>{emailTemplate.appTitle || 'Default'}</td>
      <td>{emailTemplate.layoutTitle}</td>
      <td className="text-center">{emailTemplate.active ? 'Active' : 'Inactive'}</td>
      <td className="text-center">{stringAsDate(emailTemplate.dates.createdAt)}</td>
      <td className="text-center">{stringAsDate(emailTemplate.dates.updatedAt)}</td>
      <td>
        <div className="d-flex gap-1">
          <Link to={`/templates/edit/${emailTemplate.uuid}`} className="btn btn-sm btn-outline-primary">Edit</Link>
          <PreviewEmailTemplateAction uuid={emailTemplate.uuid as string} title="Preview"/>
          <SendTestButton uuid={emailTemplate.uuid}/>
        </div>
      </td>
    </tr>
  )
}

function SendTestButton ({ uuid }: { uuid: string }) {
  const [showModal, setShowModal] = useState(false)
  return (
    <>
      <Button variant="outline-success" size="sm" className="text-nowrap" onClick={() => {
        setShowModal(true)
      }}>Send Test</Button>
      <AppModal show={showModal} onHide={() => setShowModal(false)}>
        <SendTest uuid={uuid}/>
      </AppModal>
    </>
  )
}