import React from 'react'
import { Col, Placeholder, PlaceholderButton, Row } from 'react-bootstrap'
import { clsx } from 'clsx'

type TableLoadingProps = {
  colSpan?: number
}

export function TableLoading ({ colSpan }: TableLoadingProps) {
  return (
    <>
      <TableRow size={6} colSpan={colSpan}/>
      <TableRow size={4} colSpan={colSpan}/>
      <TableRow size={7} colSpan={colSpan}/>
      <TableRow size={3} colSpan={colSpan}/>
    </>
  )
}

function TableRow ({ size, colSpan = 100 }: { size: number, colSpan?: number }) {
  return (
    <tr>
      <td colSpan={colSpan}>
        <Placeholder animation="glow">
          <Placeholder xs={size} className="rounded m-2"/>
        </Placeholder>
      </td>
    </tr>
  )
}

export function FormLoading ({ colSpan }: TableLoadingProps) {
  return (
    <>
      <Row>
        <Col lg={6}>
          <FormItem label={5}/>
          <FormItem label={3}/>
          <FormItem label={5}/>
          <FormItem label={7}/>
        </Col>
        <Col lg={6}>
          <FormItem label={5}/>
          <FormItem label={3}/>
          <FormItem label={4}/>
        </Col>
      </Row>
      <div className="d-flex justify-content-end gap-2">
        <PlaceholderButton variant='secondary' style={{ width: 100 }}/>
        <PlaceholderButton style={{ width: 100 }}/>
      </div>
    </>
  )
}

type FormItemProps = {
  label: number,
  input?: number,
  inputClass?: string
}

function FormItem ({ label, input = 12, inputClass }: FormItemProps) {
  return (
    <div className="mb-3">
      <Placeholder animation="glow">
        <Placeholder xs={label} className="mb-2 rounded"/>
      </Placeholder>
      <Placeholder animation="glow">
        <Placeholder xs={input} className={clsx('py-3 rounded', inputClass)}/>
      </Placeholder>
    </div>
  )
}
