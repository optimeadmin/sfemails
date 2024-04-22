import { Layout } from '../types'
import { axiosApi } from './axiosInstances'

export async function getLayouts (signal: AbortSignal): Promise<Layout[]> {
  const { data } = await axiosApi.get(`/layouts`, { signal })

  return data as Layout[]
}