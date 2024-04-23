import React from 'react'
import { ExistentLayout } from '../../types'
import { stringAsDate } from '../../utils/dates'
import { Link } from 'react-router-dom'
import { PreviewLayoutAction } from './PreviewLayoutAction.tsx'

export function LayoutItem ({ layout }: { layout: ExistentLayout }) {
  return (
    <tr className="table-row-middle">
      <td>{layout.id}</td>
      <td>
        {layout.description}
        <small className="d-block uuid-small">{layout.uuid}</small>
      </td>
      <td>{stringAsDate(layout.dates.createdAt)}</td>
      <td>{stringAsDate(layout.dates.updatedAt)}</td>
      <td>
        <div className="d-flex gap-1">
          <Link to={`/layouts/edit/${layout.uuid}`} className="btn btn-sm btn-outline-primary">Edit</Link>
          <PreviewLayoutAction uuid={layout.uuid as string} title={'Preview'}/>
        </div>
      </td>
    </tr>
  )
}