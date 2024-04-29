import React from 'react'
import { EmailLog } from '../../types'
import { stringAsDateTime } from '../../utils/dates'
import { Button, OverlayTrigger, Popover } from 'react-bootstrap'
import { ButtonVariant } from 'react-bootstrap/types'
import { ShowVarsButton } from './actions/ShowVarsButton.tsx'
import { ShowButton } from './actions/ShowButton.tsx'
import { ResendButton } from './actions/ResendButton.tsx'

export function EmailLogItem ({ emailLog }: { emailLog: EmailLog }) {
  return (
    <tr className="table-row-middle">
      <td>
        <div className="border-bottom pb-1">
          <small className="user-select-all">{emailLog.configCode}</small>
          <small> {`{${emailLog.locale}}`}</small>
          {emailLog.resend && <small className="fst-italic"> [Resend]</small>}
        </div>
        {emailLog.emailSubject}
      </td>
      <td className="user-select-all">{emailLog.recipient}</td>
      <td>{emailLog.sessionUser}</td>
      <td className="text-center">{stringAsDateTime(emailLog.sendAt)}</td>
      <td className="text-center px-3"><Status emailLog={emailLog}/></td>
      <td>
        <div className="d-flex gap-1">
          <ShowVarsButton vars={emailLog.vars}/>
          <ShowButton uuid={emailLog.uuid} status={emailLog.status}/>
          <ResendButton uuid={emailLog.uuid} disabled={!emailLog.canResend}/>
        </div>
      </td>
    </tr>
  )
}

function Status ({ emailLog }: { emailLog: EmailLog }) {
  const { status, statusTitle, error } = emailLog
  const btnVariant: ButtonVariant = status === 'send' ? 'outline-secondary' : 'outline-danger'

  return (
    <OverlayTrigger trigger="focus" overlay={<Popover><Popover.Body>{error}</Popover.Body></Popover>}>
      <Button className="w-100" size="sm" variant={btnVariant} disabled={status === 'send'}>
        {emailLog.statusTitle}
      </Button>
    </OverlayTrigger>
  )
}