import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout'
import { Table } from 'react-bootstrap'
import { Link } from 'react-router-dom'
import { useGetTemplates } from '../../hooks/templates.ts'
import { EmailTemplateItem } from '../../components/template/EmailTemplateItem.tsx'
import { TableLoading } from '../../components/ui/loading.tsx'
import { NoItems } from '../../components/ui/table/NoItems.tsx'

export function TemplatesPage () {
  const { isLoading, templates } = useGetTemplates()

  return (
    <PageLayout>
      <PageHeader title={'Emails Templates'} actions={
        <Link to="/templates/create" className="btn btn-primary">Create</Link>
      }/>

      <Table striped responsive>
        <thead>
          <tr>
            <th>Information</th>
            <th className="">Application</th>
            <th className="">Layout</th>
            <th className="text-center">Status</th>
            <th className="text-center" style={{ width: 150 }}>Created At</th>
            <th className="text-center" style={{ width: 150 }}>Updated At</th>
            <th className="text-center" style={{ width: 210 }}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {isLoading && <TableLoading/>}
          <NoItems isLoading={isLoading} items={templates}/>
          {templates?.map(template => (
            <EmailTemplateItem key={template.uuid} emailTemplate={template}/>
          ))}
        </tbody>
      </Table>
    </PageLayout>
  )
}