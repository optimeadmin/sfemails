import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link, useParams } from 'react-router-dom'
import { ConfigForm } from '../../components/config/ConfigForm.tsx'
import { useGetConfigByUuid } from '../../hooks/config.ts'

export function EditConfigPage () {
  const { uuid } = useParams()
  const { isLoading, config } = useGetConfigByUuid(uuid!)

  return (
    <PageLayout>
      <PageHeader title={'Create Email Config'} actions={
        <Link to="/" className="btn btn-outline-secondary">Back</Link>
      }/>

      <ConfigForm config={config} key={config?.uuid}/>
    </PageLayout>
  )
}