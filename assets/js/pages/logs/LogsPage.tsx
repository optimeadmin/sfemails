import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout.tsx'
import { Table } from 'react-bootstrap'
import { useGetLogs } from '../../hooks/logs.ts'
import { QueryDataPagination } from '../../components/ui/pagination/QueryDataPagination.tsx'
import { EmailLogItem } from '../../components/logs/EmailLogItem.tsx'

export function LogsPage () {
  const { logs, paginationData } = useGetLogs()

  console.log(logs, paginationData)

  const pagination = <QueryDataPagination paginationData={paginationData}/>

  return (
    <PageLayout>
      <PageHeader title={'Emails Logs'}/>

      {pagination}

      <Table striped size="sm">
        <thead>
          <tr>
            <th className="text-center" style={{ width: 140 }}>Status</th>
            <th>Email info</th>
            <th className="">Recipient</th>
            <th className="">Session User</th>
            <th className="text-center">Send At</th>
            <th className="text-center" style={{ width: 220 }}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {logs?.map(log => (
            <EmailLogItem key={log.uuid} emailLog={log}/>
          ))}
        </tbody>
      </Table>

      {pagination}

    </PageLayout>
  )
}