import React from 'react'
import { ButtonModal } from '../../ui/ButtonModal.tsx'
import { Badge, Modal } from 'react-bootstrap'
import { CloseModal } from '../../ui/AppModal.tsx'
import { Iframe } from '../../preview/Preview.tsx'
import { EmailStatus } from '../../../types'
import { useUrl } from '../../../contexts/UrlContext.tsx'
import { clsx } from 'clsx'

type ResendProps = {
  uuid: string,
  status: EmailStatus
}

export function ResendButton ({ uuid, status }: ResendProps) {
  const disabled = ['pending', 'no_template'].includes(status)

  return (
    <ButtonModal
      modalProps={{ size: 'lg' }}
      modalContent={<ShowContent uuid={uuid} status={status}/>}
      variant="secondary"
      size="sm"
      className={clsx(disabled && 'opacity-25')}
      disabled={disabled}
    >
      Resend
    </ButtonModal>
  )
}

function ShowContent ({ uuid, status }: ResendProps) {
  const { apiUrl } = useUrl()
  const logUrl = `${apiUrl}/logs/${uuid}`

  return (
    <>
      <Modal.Header closeButton className="d-flex gap-3">
        <Badge bg={status !== 'send' ? 'danger' : 'success'} className="py-2 px-3">
          {status === 'send' ? 'Success' : 'Error'}
        </Badge>
        <Modal.Title className="fs-5">
          Log Content
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Iframe url={logUrl}/>
      </Modal.Body>
      <Modal.Footer>
        <CloseModal>Close</CloseModal>
      </Modal.Footer>
    </>
  )
}