import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Table } from 'react-bootstrap'
import { LayoutItem } from '../../components/layout/LayoutItem'
import { Link } from 'react-router-dom'
import { useGetLayouts } from '../../hooks/layout'
import { TableLoading } from '../../components/ui/loading.tsx'
import { NoItems } from '../../components/ui/table/NoItems.tsx'

export function LayoutsPage () {
  const { isLoading, layouts } = useGetLayouts()

  return (
    <PageLayout>
      <PageHeader title={'Email Layout List'} actions={
        <Link to="/layouts/create" className="btn btn-primary">Create</Link>
      }/>

      <Table striped responsive>
        <thead>
          <tr>
            <th>ID</th>
            <th>Description</th>
            <th style={{ width: 150 }}>Created At</th>
            <th style={{ width: 150 }}>Updated At</th>
            <th style={{ width: 150 }}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {isLoading && <TableLoading/>}
          <NoItems isLoading={isLoading} items={layouts}/>
          {layouts?.map(layout => (
            <LayoutItem key={layout.id} layout={layout}/>
          ))}
        </tbody>
      </Table>
    </PageLayout>
  )
}