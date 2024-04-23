import React, { useState } from 'react'
import { ExistentConfig } from '../../types'
import { stringAsDate } from '../../utils/dates'
import { Button, Modal } from 'react-bootstrap'
import { useUrl } from '../../contexts/UrlContext'
import { Preview } from '../preview/Preview'
import { Link } from 'react-router-dom'
import { PreviewLayoutAction } from '../layout/PreviewLayoutAction.tsx'

export function ConfigItem ({ config }: { config: ExistentConfig }) {
  return (
    <tr className="table-row-middle">
      <td>{config.code}</td>
      <td>
        {config.description}
        <small className="d-block uuid-small">{config.uuid}</small>
      </td>
      <td>{stringAsDate(config.dates.createdAt)}</td>
      <td>{stringAsDate(config.dates.updatedAt)}</td>
      <td>
        <div className="d-flex gap-1">
          <Link to={`/config/edit/${config.uuid}`} className="btn btn-sm btn-outline-primary">Edit</Link>
          <PreviewLayoutAction uuid={config.layoutUuid as string} title='Preview Layout'/>
        </div>
      </td>
    </tr>
  )
}