import React from 'react'
import { Button, Popover } from 'react-bootstrap'
import { clsx } from 'clsx'
import { AppOverlayTrigger, HidePopoverButton, useHidePopover } from '../../ui/AppOverlayTrigger.tsx'
import { useResendEmail } from '../../../hooks/logs.ts'
import { ButtonWithLoading } from '../../ui/ButtonWithLoading.tsx'
import { toast } from 'react-toastify'

type ResendProps = {
  uuids: string[],
  disabled?: boolean,
}

export function ResendButton ({ uuids, disabled = false }: ResendProps) {
  const popover = (
    <Popover>
      <PopoverContent uuids={uuids}/>
    </Popover>
  )

  return (
    <AppOverlayTrigger
      placement="auto-end"
      overlay={popover}
    >
      <Button
        size="sm"
        variant="secondary"
        className={clsx(disabled && 'opacity-25')}
        disabled={disabled}
      >Resend</Button>
    </AppOverlayTrigger>
  )
}

function PopoverContent ({ uuids }: { uuids: string[] }) {
  const { isPending, resend } = useResendEmail(uuids)
  const hidePopover = useHidePopover()

  async function confirm () {
    const status = await resend()
    hidePopover()

    if (status === 207) {
      toast.warn(uuids.length > 1 ? 'Emails failed to resend' : 'Email failed to resend')
    } else {
      toast.success(uuids.length > 1 ? 'Emails sent successfully!' : 'Email sent successfully!', {
        autoClose: 1500
      })
    }
  }

  return (
    <Popover.Body>
      <p>
        Are you sure to resend {uuids.length > 1 ? 'the emails' : 'this email'}?
      </p>
      <div className="d-flex gap-2 justify-content-center">
        {!isPending && <HidePopoverButton size="sm" variant="outline-secondary">No</HidePopoverButton>}
        <ButtonWithLoading size="sm" variant="dark" onClick={confirm} isLoading={isPending}>
          {isPending ? 'Sending' : 'Yes'}
        </ButtonWithLoading>
      </div>
    </Popover.Body>
  )
}