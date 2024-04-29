import { EmailLog } from '../types'
import { axiosApi } from './axiosInstances'
import { PaginationDataResult } from '../components/ui/pagination/types'

export async function getLogs (queryData: Object, signal: AbortSignal): Promise<PaginationDataResult<EmailLog>> {
  const { data } = await axiosApi.get(`/logs`, { signal, params: queryData, })

  return data as PaginationDataResult<EmailLog>
}

export async function resendEmails (uuids: string[]): Promise<number> {
  const { data, status } = await axiosApi.post(`/logs`, { uuids })

  return status
}