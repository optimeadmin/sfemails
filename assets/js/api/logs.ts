import {
  Config,
  EmailTemplate,
  EmailTemplateVars,
  EmailTestValues,
  ExistentConfig,
  ExistentEmailTemplate
} from '../types'
import { axiosApi } from './axiosInstances'

export async function getLogs (signal: AbortSignal): Promise<ExistentEmailTemplate[]> {
  const { data } = await axiosApi.get(`/logs`, { signal })

  return data as ExistentEmailTemplate[]
}