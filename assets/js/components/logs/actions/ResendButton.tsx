import React from 'react'
import { Button, Popover } from 'react-bootstrap'
import { EmailStatus } from '../../../types'
import { clsx } from 'clsx'
import { AppOverlayTrigger, HidePopoverButton, useHidePopover } from '../../ui/AppOverlayTrigger.tsx'
import { useResendEmail } from '../../../hooks/logs.ts'
import { ButtonWithLoading } from '../../ui/ButtonWithLoading.tsx'
import { toast } from 'react-toastify'

type ResendProps = {
  uuid: string,
  status: EmailStatus
}

export function ResendButton ({ uuid, status }: ResendProps) {
  const disabled = ['pending', 'no_template'].includes(status)

  const popover = (
    <Popover>
      <PopoverContent uuid={uuid}/>
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

function PopoverContent ({ uuid }: { uuid: string }) {
  const { isPending, resend } = useResendEmail([uuid])
  const hidePopover = useHidePopover()

  async function confirm () {
    try {
      const status = await resend()
      hidePopover()

      if (status === 207) {
        toast.warn('Email failed to resend')
      } else {
        toast.success('Email sent successfully!', {
          autoClose: 1500
        })
      }
    } catch (e) {
      toast.error('Ups, an error has occurred!', { autoClose: 1000 })
    }
  }

  return (
    <Popover.Body>
      <p>
        Are you sure to resend this email?
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