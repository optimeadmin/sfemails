import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link } from 'react-router-dom'
import { ConfigForm } from '../../components/config/ConfigForm.tsx'
import { useGetLayouts } from '../../hooks/layout.ts'

export function CreateConfigPage () {
  const { isLoading: isLoadingLayouts } = useGetLayouts()
  const showLoading = isLoadingLayouts

  return (
    <PageLayout>
      <PageHeader title={'Create Email Config'} actions={
        <Link to="/" className="btn btn-outline-secondary">Back</Link>
      }/>

      {showLoading
        ? <h1>Loading...</h1>
        : <ConfigForm/>
      }
    </PageLayout>
  )
}