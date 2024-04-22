import { ExistentLayout } from '../types'
import { axiosApi } from './axiosInstances'

export async function getLayouts (signal: AbortSignal): Promise<ExistentLayout[]> {
  const { data } = await axiosApi.get(`/layouts`, { signal })

  return data as ExistentLayout[]
}