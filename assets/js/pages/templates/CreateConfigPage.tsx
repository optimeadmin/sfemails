import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link } from 'react-router-dom'
import { ConfigForm } from '../../components/config/ConfigForm.tsx'

export function CreateConfigPage () {
  return (
    <PageLayout>
      <PageHeader title={'Create Email Config'} actions={
        <Link to="/" className="btn btn-outline-secondary">Back</Link>
      }/>

      <ConfigForm/>
    </PageLayout>
  )
}