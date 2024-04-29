import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout.tsx'
import { Table } from 'react-bootstrap'
import { useGetLogs } from '../../hooks/logs.ts'
import { QueryDataPagination } from '../../components/ui/pagination/QueryDataPagination.tsx'
import { EmailLogItem } from '../../components/logs/EmailLogItem.tsx'
import { LogsFilters } from '../../components/logs/LogsFilters.tsx'
import { useApps } from '../../contexts/AppsContext.tsx'

export function LogsPage () {
  const { isLoading, logs, paginationData } = useGetLogs()
  const { appsCount } = useApps()

  const pagination = <div className="d-flex justify-content-end mb-2">
    <QueryDataPagination paginationData={paginationData} className="pagination-sm"/>
  </div>

  return (
    <PageLayout>
      <PageHeader title={'Emails Logs'}/>

      <LogsFilters/>

      {pagination}

      <Table striped size="sm">
        <thead>
          <tr>
            <th>Email info</th>
            <th className="">Recipient</th>
            <th className="">Session User</th>
            {appsCount > 1 && <th className="">Application</th>}
            <th className="text-center">Send At</th>
            <th className="text-center" style={{ width: 140 }}>Status</th>
            <th className="text-center" style={{ width: 220 }}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {logs?.map(log => (
            <EmailLogItem key={log.uuid} emailLog={log}/>
          ))}
          {!isLoading && (logs?.length ?? 0) === 0 && (
            <tr>
              <td colSpan={10} className="text-center">
                <div className="py-2">
                  No items found
                </div>
              </td>
            </tr>
          )}
        </tbody>
      </Table>

      {pagination}

    </PageLayout>
  )
}