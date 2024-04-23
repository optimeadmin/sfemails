import React from 'react'
import { ExistentConfig } from '../../types'
import { stringAsDate } from '../../utils/dates'
import { Link } from 'react-router-dom'
import { PreviewLayoutAction } from '../layout/PreviewLayoutAction.tsx'

export function ConfigItem ({ config }: { config: ExistentConfig }) {
  const showDescription = config.description && config.description !== config.code
  return (
    <tr className="table-row-middle">
      <td>
        <div className="user-select-all mb-2">{config.code}</div>
        <div className="text-opacity-75 text-dark">{showDescription && config.description}</div>
        <small className="d-block uuid-small user-select-all">{config.uuid}</small>
      </td>
      <td>{config.target}</td>
      <td className="text-center">{config.editable ? 'Yes' : 'No'}</td>
      <td className="text-center">{stringAsDate(config.dates.createdAt)}</td>
      <td className="text-center">{stringAsDate(config.dates.updatedAt)}</td>
      <td>
        <div className="d-flex gap-1">
          <Link to={`/config/edit/${config.uuid}`} className="btn btn-sm btn-outline-primary">Edit</Link>
          <PreviewLayoutAction uuid={config.layoutUuid as string} title="Preview Layout"/>
        </div>
      </td>
    </tr>
  )
}