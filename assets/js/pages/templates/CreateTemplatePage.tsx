import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link } from 'react-router-dom'
import { TemplateForm } from '../../components/template/TemplateForm.tsx'

export function CreateTemplatePage () {
  return (
    <PageLayout>
      <PageHeader title={'Create Email Template'} actions={
        <Link to="/templates" className="btn btn-outline-secondary">Back</Link>
      }/>

      <TemplateForm/>
    </PageLayout>
  )
}