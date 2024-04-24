import { axiosApi } from './axiosInstances'
import { EmailApp } from '../types'

export async function getEmailApps (signal: AbortSignal): Promise<EmailApp[]> {
  const { data } = await axiosApi.get(`/email-apps`, { signal })

  return data as EmailApp[]
}