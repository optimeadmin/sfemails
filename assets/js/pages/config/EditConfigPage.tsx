import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link, useParams } from 'react-router-dom'
import { ConfigForm } from '../../components/config/ConfigForm.tsx'
import { useGetConfigByUuid } from '../../hooks/config.ts'
import { useGetLayouts } from '../../hooks/layout.ts'

export function EditConfigPage () {
  const { uuid } = useParams()
  const { isLoading, config } = useGetConfigByUuid(uuid!)
  const { isLoading: isLoadingLayouts } = useGetLayouts()
  const showLoading = isLoading || isLoadingLayouts

  return (
    <PageLayout>
      <PageHeader
        title="Edit Email Config"
        subtitle={uuid}
        actions={<Link to="/" className="btn btn-outline-secondary">Back</Link>}
      />

      {showLoading
        ? <h1>Loading...</h1>
        : <ConfigForm config={config} key={config?.uuid}/>
      }
    </PageLayout>
  )
}