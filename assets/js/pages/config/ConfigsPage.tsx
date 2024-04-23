import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Table } from 'react-bootstrap'
import { Link } from 'react-router-dom'
import { useGetConfigs } from '../../hooks/config.ts'
import { ConfigItem } from '../../components/config/ConfigItem.tsx'

export function ConfigsPage () {
  const { configs } = useGetConfigs()

  console.log(configs?.[0])

  return (
    <PageLayout>
      <PageHeader title={'Emails Configuration'} actions={
        <Link to="/config/create" className="btn btn-primary">Create</Link>
      }/>

      <Table>
        <thead>
          <tr>
            <th>Code</th>
            <th>Description</th>
            <th style={{ width: 150 }}>Created At</th>
            <th style={{ width: 150 }}>Updated At</th>
            <th style={{ width: 200 }}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {configs?.map(config => (
            <ConfigItem key={config.uuid} config={config}/>
          ))}
        </tbody>
      </Table>
    </PageLayout>
  )
}