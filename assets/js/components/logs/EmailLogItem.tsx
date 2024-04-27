import React from 'react'
import { EmailLog } from '../../types'
import { stringAsDateTime } from '../../utils/dates'
import { Link } from 'react-router-dom'
import { Button, OverlayTrigger, Popover } from 'react-bootstrap'
import { ButtonVariant } from 'react-bootstrap/types'

export function EmailLogItem ({ emailLog }: { emailLog: EmailLog }) {
  return (
    <tr className="table-row-middle">
      <td className="text-center px-3"><Status emailLog={emailLog}/></td>
      <td>
        <small className="user-select-all d-block border-bottom pb-1">
          {emailLog.configCode} {`{${emailLog.locale}}`}
        </small>
        {emailLog.emailSubject}
      </td>
      <td>{emailLog.recipient}</td>
      <td>{emailLog.sessionUser}</td>
      <td className="text-center">{stringAsDateTime(emailLog.sendAt)}</td>
      <td>
        <div className="d-flex gap-1">
          <Link to={`/templates/edit/${emailLog.uuid}`} className="btn btn-sm btn-outline-primary">Edit</Link>
        </div>
      </td>
    </tr>
  )
}

function Status ({ emailLog }: { emailLog: EmailLog }) {
  const { status, statusTitle, error } = emailLog
  const btnVariant: ButtonVariant = status === 'send' ? 'outline-secondary' : 'outline-danger'

  return (
    <OverlayTrigger trigger='focus' overlay={<Popover><Popover.Body>{error}</Popover.Body></Popover>}>
      <Button className="w-100" size="sm" variant={btnVariant} disabled={status === 'send'}>
        {emailLog.statusTitle}
      </Button>
    </OverlayTrigger>
  )
}