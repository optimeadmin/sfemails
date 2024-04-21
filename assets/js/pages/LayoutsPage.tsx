import React from 'react'
import { PageHeader, PageLayout } from '../components/ui/layout/PageLayout'
import { Button } from 'react-bootstrap'
import { useQuery } from '@tanstack/react-query'

export function LayoutsPage () {
  // useQuery({})

  return (
    <PageLayout>
      <PageHeader title={'Email Layout List'} actions={<Button>Create</Button>}/>

      acá la lista
    </PageLayout>
  )
}