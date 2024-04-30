import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link, useParams } from 'react-router-dom'
import { LayoutForm } from '../../components/layout/LayoutForm'
import { useGetLayoutByUuid } from '../../hooks/layout'
import { FormLoading } from '../../components/ui/loading.tsx'

export function EditLayoutPage () {
  const { uuid } = useParams()
  const { isLoading, layout } = useGetLayoutByUuid(uuid!)

  return (
    <PageLayout>
      <PageHeader title="Edit Layout" subtitle={uuid} actions={
        <Link to="/layouts" className="btn btn-outline-secondary">Back</Link>
      }/>

      {isLoading
        ? <FormLoading/>
        : <LayoutForm key={layout?.id} layout={layout}/>}
    </PageLayout>
  )
}