import React from 'react'
import { EmailLog } from '../../types'
import { stringAsDateTime } from '../../utils/dates'
import { Button, OverlayTrigger, Popover } from 'react-bootstrap'
import { ButtonVariant } from 'react-bootstrap/types'
import { ShowVarsButton } from './actions/ShowVarsButton.tsx'
import { ShowButton } from './actions/ShowButton.tsx'
import { ResendButton } from './actions/ResendButton.tsx'
import { useApps } from '../../contexts/AppsContext.tsx'
import FormCheckInput from 'react-bootstrap/FormCheckInput'
import { ToggleSelectedType } from '../../hooks/selectedItems.ts'
import { Link } from 'react-router-dom'
import { stringify } from 'qs'

type EmailLogItemProps = {
  emailLog: EmailLog,
  toggleSelected: ToggleSelectedType<EmailLog>
  selected: boolean,
}

export function EmailLogItem ({ emailLog, toggleSelected, selected }: EmailLogItemProps) {
  const { appsCount } = useApps()

  return (
    <tr className="table-row-middle">
      <td className="text-center">
        <FormCheckInput
          onChange={() => toggleSelected(emailLog)}
          checked={selected}
          disabled={!emailLog.canResend}
        />
      </td>
      <td>
        <div className="border-bottom pb-1">
          <small className="user-select-all">{emailLog.configCode}</small>
          <small> {`{${emailLog.locale}}`}</small>
          {emailLog.resend && <small className="fst-italic"> [Resend]</small>}
        </div>
        {emailLog.emailSubject}
      </td>
      <td className="user-select-all"><Recipient recipient={emailLog.recipient}/></td>
      <td>{emailLog.sessionUser}</td>
      {appsCount > 1 && <td>{emailLog.application || '--'}</td>}
      <td className="text-center text-nowrap">{stringAsDateTime(emailLog.sendAt)}</td>
      <td className="text-center px-3"><Status emailLog={emailLog}/></td>
      <td>
        <div className="d-flex gap-1">
          <ShowVarsButton uuid={emailLog.uuid} vars={emailLog.vars}/>
          <ShowButton uuid={emailLog.uuid} status={emailLog.status}/>
          <ResendButton uuids={[emailLog.uuid]} disabled={!emailLog.canResend}/>
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

function Recipient ({ recipient }: { recipient: string }) {
  const url = `/logs?${stringify({ recipients: recipient })}`
  return (
    <Link to={url} state={{ selectedRecipient: recipient }}>{recipient}</Link>
  )
}