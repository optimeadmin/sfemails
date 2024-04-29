import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout.tsx'
import { Table } from 'react-bootstrap'
import { useGetLogs } from '../../hooks/logs.ts'
import { QueryDataPagination } from '../../components/ui/pagination/QueryDataPagination.tsx'
import { EmailLogItem } from '../../components/logs/EmailLogItem.tsx'
import { LogsFilters } from '../../components/logs/LogsFilters.tsx'

export function LogsPage () {
  const { logs, paginationData } = useGetLogs()

  const pagination = <div className="d-flex justify-content-end mb-2">
    <QueryDataPagination paginationData={paginationData} className='pagination-sm'/>
  </div>

  return (
    <PageLayout>
      <PageHeader title={'Emails Logs'}/>

      <LogsFilters />

      {pagination}

      <Table striped size="sm">
        <thead>
          <tr>
            <th>Email info</th>
            <th className="">Recipient</th>
            <th className="">Session User</th>
            <th className="text-center">Send At</th>
            <th className="text-center" style={{ width: 140 }}>Status</th>
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