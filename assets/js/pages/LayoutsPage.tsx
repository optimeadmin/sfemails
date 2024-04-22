import React from 'react'
import { PageHeader, PageLayout } from '../components/ui/layout/PageLayout'
import { Button, Table } from 'react-bootstrap'
import { useQuery } from '@tanstack/react-query'
import { getLayouts } from '../api/layouts'
import { LayoutItem } from '../components/layout/LayoutItem'

export function LayoutsPage () {
  const { data: layouts } = useQuery({
    queryKey: ['layouts'],
    queryFn: ({ signal }) => getLayouts(signal),
  })

  return (
    <PageLayout>
      <PageHeader title={'Email Layout List'} actions={<Button>Create</Button>}/>

      <Table>
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
          {layouts?.map(layout => (
            <LayoutItem key={layout.id} layout={layout}/>
          ))}
        </tbody>
      </Table>
    </PageLayout>
  )
}