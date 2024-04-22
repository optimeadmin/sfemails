import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Link } from 'react-router-dom'
import { LayoutForm } from '../../components/layout/LayoutForm'

export function CreateLayoutPage () {
  return (
    <PageLayout>
      <PageHeader title={'Create Layout'} actions={
        <Link to="/layouts" className="btn btn-outline-secondary">Back</Link>
      }/>

      <LayoutForm/>
    </PageLayout>
  )
}