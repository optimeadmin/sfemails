import React from 'react'
import { ButtonModal } from '../../ui/ButtonModal.tsx'
import { EmailLogVars } from '../../../types'
import { Modal, Table } from 'react-bootstrap'
import { CloseModal } from '../../ui/AppModal.tsx'

export const ShowVarsButton = React.memo(({ uuid, vars }: { uuid: string, vars: EmailLogVars }) => {
  return (
    <ButtonModal
      modalProps={{ size: 'lg' }}
      modalContent={<VarsContent uuid={uuid} vars={vars}/>}
      variant="outline-secondary"
      size="sm"
      className="text-nowrap"
    >
      Show Vars
    </ButtonModal>
  )
})

function VarsContent ({ uuid, vars }: { uuid: string, vars: EmailLogVars }) {
  const mappedVars = Object.entries(vars).map(([key, value]) => ({
    key,
    value: typeof value === 'object' ? JSON.stringify(value, null, 2) : String(value),
  } as { key: string, value: string }))

  return (
    <>
      <Modal.Header closeButton>
        <Modal.Title className="fs-5">
          Email Variables
          <small className='fs-6 text-muted fst-italic ms-3 user-select-all'>{uuid}</small>
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Table bordered>
          <tbody>
            {mappedVars.map(item => (
              <tr key={item.key}>
                <td className="user-select-all">{item.key}</td>
                <td className="user-select-all">{item.value}</td>
              </tr>
            ))}
          </tbody>
        </Table>
      </Modal.Body>
      <Modal.Footer>
        <CloseModal>Close</CloseModal>
      </Modal.Footer>
    </>
  )
}