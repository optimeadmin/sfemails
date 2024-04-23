import { Config, ExistentConfig, ExistentLayout } from '../types'
import { axiosApi } from './axiosInstances'

export async function getConfigs (signal: AbortSignal): Promise<ExistentConfig[]> {
  const { data } = await axiosApi.get(`/configs`, { signal })

  return data as ExistentConfig[]
}

export async function getLayout (uuid: string, signal: AbortSignal): Promise<ExistentLayout> {
  const { data } = await axiosApi.get(`/layouts/${uuid}`, { signal })

  return data as ExistentLayout
}

export async function saveConfig (config: Config | ExistentConfig): Promise<void> {
  if ('uuid' in config && config.uuid) {
    await axiosApi.patch(`/configs/${config.uuid}`, config)
  } else {
    await axiosApi.post(`/configs`, config)
  }
}