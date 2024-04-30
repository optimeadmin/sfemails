import React from 'react'
import { PageHeader, PageLayout } from '../../components/ui/layout/PageLayout.tsx'
import { Table } from 'react-bootstrap'
import { useGetLogs } from '../../hooks/logs.ts'
import { QueryDataPagination } from '../../components/ui/pagination/QueryDataPagination.tsx'
import { EmailLogItem } from '../../components/logs/EmailLogItem.tsx'
import { LogsFilters } from '../../components/logs/LogsFilters.tsx'
import { useApps } from '../../contexts/AppsContext.tsx'
import { TableLoading } from '../../components/ui/loading.tsx'
import { NoItems } from '../../components/ui/table/NoItems.tsx'
import FormCheckInput from 'react-bootstrap/FormCheckInput'
import { useSelectedItems } from '../../hooks/selectedItems.ts'
import { EmailLog } from '../../types'
import { ResendButton } from '../../components/logs/actions/ResendButton.tsx'

export function LogsPage () {
  const { isLoading, logs, paginationData } = useGetLogs()
  const { appsCount } = useApps()
  const { isItemSelected, toggleSelectedItem, toggleAll, isSelectedAll, selectedItems } = useSelectedItems(
    logs,
    (item: EmailLog) => item.canResend ? item.uuid : false
  )

  const pagination = <div className="ms-auto d-flex justify-content-end mb-2">
    <QueryDataPagination paginationData={paginationData} className="pagination-sm"/>
  </div>

  return (
    <PageLayout>
      <PageHeader title={'Emails Logs'}/>

      <LogsFilters/>

      <div className="d-flex justify-content-between align-items-start gap-2">
        <ResendAction uuids={selectedItems}/>
        {pagination}
      </div>

      <Table striped size="sm" responsive>
        <thead>
          <tr>
            <th className="text-center" style={{ width: 40 }}>
              <FormCheckInput checked={isSelectedAll} onChange={() => toggleAll()}/>
            </th>
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
          {isLoading && <TableLoading/>}
          <NoItems isLoading={isLoading} items={logs}/>
          {logs?.map(log => (
            <EmailLogItem
              key={log.uuid}
              emailLog={log}
              toggleSelected={toggleSelectedItem}
              selected={isItemSelected(log)}
            />
          ))}
        </tbody>
      </Table>

      {pagination}

    </PageLayout>
  )
}

function ResendAction ({ uuids }: { uuids: string[] }) {
  return (
    <ResendButton uuids={uuids} disabled={uuids.length === 0}/>
  )
}