import React from 'react'
import { FormErrors, FormLabel, FormRow } from '../ui/form/field'
import { Col, Form, FormControl, FormSelect, Row } from 'react-bootstrap'
import { ActionsContainer } from '../ui/form/ActionsContainer'
import { Link } from 'react-router-dom'
import { FormProvider, useWatch } from 'react-hook-form'
import { ExistentConfig } from '../../types'
import { ButtonWithLoading } from '../ui/ButtonWithLoading'
import { PreviewLayoutAction } from '../layout/PreviewLayoutAction.tsx'
import { useConfigForm } from '../../hooks/config.ts'
import { CreateLayoutInConfig } from './CreateLayoutInConfig.tsx'

export function ConfigForm ({ config }: { config?: ExistentConfig }) {
  const { isLoadingLayouts, isPending, layouts, form, sendForm } = useConfigForm(config)
  const { register } = form
  const isEdit = !!config

  return (
    <form onSubmit={form.handleSubmit(sendForm, (error) => console.log('error', error, form.getValues()))}>
      <FormProvider {...form}>
        <Row>
          <Col xs={12} lg={6}>
            <FormRow className="mb-3" name="code">
              <FormLabel>Code</FormLabel>
              <FormControl {...register('code', { required: true })}/>
              <FormErrors/>
            </FormRow>
            <FormRow className="mb-3" name="description">
              <FormLabel>Description</FormLabel>
              <FormControl as="textarea" {...register('description', { required: true })}/>
              <FormErrors/>
            </FormRow>
          </Col>
          <Col>
            <FormRow className="mb-3" name="layoutUuid">
              <FormLabel>Layout</FormLabel>
              <div className="d-flex gap-1">
                <FormSelect {...register('layoutUuid', { required: true })}>
                  {layouts?.map(({ uuid, description }) => (
                    <option key={uuid} value={uuid}>{description}</option>
                  ))}
                </FormSelect>
                <LayoutPreview/>
                <CreateLayoutInConfig />
              </div>
              <FormErrors/>
            </FormRow>
            <Row>
              <FormRow className="mb-3 col-sm-6" name="target">
                <FormLabel>Target</FormLabel>
                <FormControl {...register('target', { required: true })}/>
                <FormErrors/>
              </FormRow>
              <FormRow className="mb-3 col-sm-6" name="editable">
                <FormLabel className="d-none d-sm-inline-block"></FormLabel>
                <Form.Switch
                  className="mt-sm-3"
                  id="form_email_config_editable_field"
                  {...register('editable')}
                  label="Editable"
                />
                <FormErrors/>
              </FormRow>
            </Row>
          </Col>
        </Row>
      </FormProvider>

      <ActionsContainer>
        <Link to="/" className="btn btn-outline-secondary" data-bs-hide>Cancel</Link>
        <ButtonWithLoading type="submit" isLoading={isPending}>{isEdit ? 'Save' : 'Create'}</ButtonWithLoading>
      </ActionsContainer>
    </form>
  )
}

function LayoutPreview () {
  const layoutUuid = useWatch({ name: 'layoutUuid' })

  return (
    <PreviewLayoutAction uuid={layoutUuid} title="Layout Preview"/>
  )
}