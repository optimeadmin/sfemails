import React from 'react'
import { ExistentEmailTemplate } from '../../types'
import { stringAsDate } from '../../utils/dates'
import { Link } from 'react-router-dom'
import { PreviewEmailTemplateAction } from './PreviewEmailTemplateAction.tsx'
import { SendTest } from './SendTest.tsx'
import { ButtonModal } from '../ui/ButtonModal.tsx'

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
  return (
    <ButtonModal
      modalContent={<SendTest uuid={uuid}/>}
      modalProps={{ size: 'xl' }}
      size="sm"
      variant="outline-success"
      className="text-nowrap">
      Send Test
    </ButtonModal>
  )
}