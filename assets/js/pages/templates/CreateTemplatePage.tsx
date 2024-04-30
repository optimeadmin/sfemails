import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link } from 'react-router-dom'
import { TemplateForm } from '../../components/template/TemplateForm.tsx'
import { useGetEmailApps } from '../../hooks/apps.ts'
import { useGetConfigs } from '../../hooks/config.ts'
import { useGetLayouts } from '../../hooks/layout.ts'
import { FormLoading } from '../../components/ui/loading.tsx'

export function CreateTemplatePage () {
  const { isLoading: isLoadingEmailApps } = useGetEmailApps()
  const { isLoading: isLoadingConfigs } = useGetConfigs()
  const { isLoading: isLoadingLayouts } = useGetLayouts()
  const showLoading = isLoadingEmailApps || isLoadingConfigs || isLoadingLayouts

  return (
    <PageLayout>
      <PageHeader title={'Create Email Template'} actions={
        <Link to="/templates" className="btn btn-outline-secondary">Back</Link>
      }/>

      {showLoading
        ? <FormLoading/>
        : <TemplateForm/>
      }
    </PageLayout>
  )
}