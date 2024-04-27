import {
  Config, EmailLog,
  EmailTemplate,
  EmailTemplateVars,
  EmailTestValues,
  ExistentConfig,
  ExistentEmailTemplate
} from '../types'
import { axiosApi } from './axiosInstances'
import { PaginationDataResult } from '../components/ui/pagination/types'

export async function getLogs (signal: AbortSignal): Promise<PaginationDataResult<EmailLog>> {
  const { data } = await axiosApi.get(`/logs`, { signal })

  return data as PaginationDataResult<EmailLog>
}