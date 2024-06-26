import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Table } from 'react-bootstrap'
import { Link } from 'react-router-dom'
import { useGetConfigs } from '../../hooks/config.ts'
import { ConfigItem } from '../../components/config/ConfigItem.tsx'
import { TableLoading } from '../../components/ui/loading.tsx'
import { NoItems } from '../../components/ui/table/NoItems.tsx'

export function ConfigsPage () {
  const { isLoading, configs } = useGetConfigs()

  return (
    <PageLayout>
      <PageHeader title={'Emails Configuration'} actions={
        <Link to="/config/create" className="btn btn-primary">Create</Link>
      }/>

      <Table striped responsive>
        <thead>
          <tr>
            <th>Description</th>
            <th>Layout</th>
            <th>Target</th>
            <th className="text-center">Editable</th>
            <th className="text-center" style={{ width: 150 }}>Created At</th>
            <th className="text-center" style={{ width: 150 }}>Updated At</th>
            <th style={{ width: 200 }}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {isLoading && <TableLoading/>}
          <NoItems isLoading={isLoading} items={configs}/>
          {configs?.map(config => (
            <ConfigItem key={config.uuid} config={config}/>
          ))}
        </tbody>
      </Table>
    </PageLayout>
  )
}