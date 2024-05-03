import React from 'react'
import { ButtonModal } from '../../ui/ButtonModal.tsx'
import { Badge, Modal } from 'react-bootstrap'
import { CloseModal } from '../../ui/AppModal.tsx'
import { Iframe } from '../../preview/Preview.tsx'
import { EmailStatus } from '../../../types'
import { useUrl } from '../../../contexts/UrlContext.tsx'
import { clsx } from 'clsx'

type PreviewProps = {
  uuid: string,
  status: EmailStatus
}

export const ShowButton = React.memo(({ uuid, status }: PreviewProps) => {
  const disabled = status === 'no_template'
  return (
    <ButtonModal
      className={clsx(disabled && 'opacity-25')}
      modalProps={{ size: 'lg' }}
      modalContent={<ShowContent uuid={uuid} status={status}/>}
      variant="dark"
      size="sm"
      disabled={disabled}
    >
      Show
    </ButtonModal>
  )
})

function ShowContent ({ uuid, status }: PreviewProps) {
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
          <small className='fs-6 text-muted fst-italic ms-3 user-select-all'>{uuid}</small>
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