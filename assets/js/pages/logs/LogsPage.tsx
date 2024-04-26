import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout.tsx'
import { Table } from 'react-bootstrap'
import { Link } from 'react-router-dom'
import { ConfigItem } from '../../components/config/ConfigItem.tsx'
import { useGetTemplates } from '../../hooks/templates.ts'
import { EmailTemplateItem } from '../../components/template/EmailTemplateItem.tsx'
import { useGetLogs } from '../../hooks/logs.ts'

export function LogsPage () {
  const { logs } = useGetLogs()

  console.log(logs)

  return (
    <PageLayout>
      <PageHeader title={'Emails Logs'} />

      <Table>
        <thead>
          <tr>
            <th>Email info</th>
            <th className="text-center">Status</th>
            <th className="">Recipient</th>
            <th className="">Session User</th>
            <th className="text-center">Send At</th>
            <th className="text-center" style={{ width: 210 }}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {/*{templates?.map(template => (*/}
          {/*  <EmailTemplateItem key={template.uuid} emailTemplate={template}/>*/}
          {/*))}*/}
        </tbody>
      </Table>
    </PageLayout>
  )
}