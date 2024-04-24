import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link, useParams } from 'react-router-dom'
import { TemplateForm } from '../../components/template/TemplateForm.tsx'
import { useGetTemplateByUuid } from '../../hooks/templates.ts'

export function EditTemplatePage () {
  const { uuid } = useParams()
  const { template } = useGetTemplateByUuid(uuid!)
  return (
    <PageLayout>
      <PageHeader title={'Edit Email Template'} subtitle={uuid} actions={
        <Link to="/templates" className="btn btn-outline-secondary">Back</Link>
      }/>

      <TemplateForm emailTemplate={template} key={template?.uuid}/>
    </PageLayout>
  )
}