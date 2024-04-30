import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link, useParams } from 'react-router-dom'
import { TemplateForm } from '../../components/template/TemplateForm.tsx'
import { useGetTemplateByUuid } from '../../hooks/templates.ts'
import { useGetEmailApps } from '../../hooks/apps.ts'
import { useGetConfigs } from '../../hooks/config.ts'
import { useGetLayouts } from '../../hooks/layout.ts'
import { FormLoading } from '../../components/ui/loading.tsx'

export function EditTemplatePage () {
  const { uuid } = useParams()
  const { template, isLoading } = useGetTemplateByUuid(uuid!)
  const { isLoading: isLoadingEmailApps } = useGetEmailApps()
  const { isLoading: isLoadingConfigs } = useGetConfigs()
  const { isLoading: isLoadingLayouts } = useGetLayouts()
  const showLoading = isLoading || isLoadingEmailApps || isLoadingConfigs || isLoadingLayouts

  return (
    <PageLayout>
      <PageHeader title={'Edit Email Template'} subtitle={uuid} actions={
        <Link to="/templates" className="btn btn-outline-secondary">Back</Link>
      }/>


      {showLoading
        ? <FormLoading/>
        : <TemplateForm emailTemplate={template} key={template?.uuid}/>
      }
    </PageLayout>
  )
}